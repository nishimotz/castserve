class CaststudioController < ApplicationController
  def rpc
    user_id = params[:uid]
    f = params[:f] || ""        # http://localhost:3000/audio/FAP_43O6571A_ST.wav
    basename = File.basename(f) # FAP_43O6571A_ST.wav
    mediaitem = Mediaitem.find_by_filepath(basename)
    unless mediaitem
      render :nothing => true, :status => 404
      return
    end
    mediaitem_id = mediaitem.id
    
    @action = params[:a]
    case @action
    when 'get_shape'
      f = params[:f]
      params[:format] = 'shape'
      @shape_array = Mediaitemshape.find(:all, :conditions=>{:mediaitem_id=>mediaitem_id}, :order=>:pos)
      headers['Content-Type'] = 'text/plain'
      # views/caststudio/rpc.shape.erb
    when 'show_info'
      params[:format] = 'info'
      # TODO episode_id
      @info = Mediaiteminfo.find_by_mediaitem_id(mediaitem_id) 
      if @info == nil
        render :nothing => true, :status => 403
        return
      end
      headers['Content-Type'] = 'text/plain'
      # views/caststudio/rpc.info.erb
    when 'save_info'
      # TODO episode_id
      info = Mediaiteminfo.find_by_mediaitem_id(mediaitem_id) 
      unless info
        info = Mediaiteminfo.new
        # TODO info.episode_id = episode_id
        info.mediaitem_id = mediaitem_id
      end
      info.color            = params[:c].to_i   ||  0
      info.media_start_time = params[:t1].to_f  ||  0   # mediaStartTime
      info.media_stop_time  = params[:t2].to_f  || -1   # mediaStopTime
      info.pos_x            = params[:px].to_i  ||  0   # posX
      info.pos_y            = params[:py].to_i  ||  0   # posY
      info.z_order          = params[:zo].to_i  ||  0   # z-order
      info.fetched          = params[:fe].to_i  ||  0   # fetched
      info.container        = params[:cs]       ||  ''  # container sheet
      info.gain             = params[:ga].to_f  ||  0   # gain as DB
      info.save
      render :nothing => true, :status => 200
    else
      render :nothing => true, :status => 403
    end
  end
  
  def run
    @episode_id  = params[:episode_id]
    @uid      = '101'
    @logging  = '1'
    # @jar_href = '/java/caststudio.jar'
    @jnlp_title = "CastStudio"
    @jnlp_heap_size = "128m"
    @jnlp_vendor    = "nishimotz"
    # @jnlp_rss       = "/mediaitem/list.rss"
    headers['Content-Type'] = 'application/x-java-jnlp-file'
  end
  
  # from CastStudio  
  #  http://ubuntu-vm:3000/caststudio/index.rss?episode_id=9977735&uid=101
  def index
    episode_id = params[:episode_id]
    #  uid = params[:uid]
    # ch = Channel.find_by_number(num)
    episode = Episode.find(episode_id)
    @title = episode.title
    # @link = 'http://localhost:3000/caststudio/rpc'
    @description = episode.channel.title
    @language = 'ja'
    @pubdate = Time.parse(Time.new.to_s).rfc822
    @ch_category = 'CastStudio'
    @ttl = 90
    @items = episode.mediaitems.dup # avoid appending to database
    # stickers 
    episode.channel.mediaitems.each do |i|
      @items.push i
    end
  end
end
