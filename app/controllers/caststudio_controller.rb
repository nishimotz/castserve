class CaststudioController < ApplicationController
  skip_before_filter :check_login

  def rpc
    mediaitem_id = params[:guid].to_i || 0 
    mediaitem = Mediaitem.find(mediaitem_id)
    unless mediaitem
      render :nothing => true, :status => 404
      return
    end
    episode_id = params[:episode_id].to_i || 0 
    
    case params[:a]
    when 'get_shape'
      # views/caststudio/rpc.shape.erb
      params[:format] = 'shape'
      @shape_array = Mediaitemshape.find(:all, :conditions=>{:mediaitem_id=>mediaitem_id}, :order=>:pos)
      headers['Content-Type'] = 'text/plain'
    when 'show_info'
      # views/caststudio/rpc.info.erb
      params[:format] = 'info'
      @info = Mediaiteminfo.find_by_mediaitem_id_and_episode_id(mediaitem_id, episode_id) 
      if @info == nil
        render :nothing => true, :status => 403
        return
      end
      headers['Content-Type'] = 'text/plain'
    when 'save_info'
      info = Mediaiteminfo.find_by_mediaitem_id_and_episode_id(mediaitem_id, episode_id) 
      unless info
        info = Mediaiteminfo.new
        info.episode_id = episode_id
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
    @jnlp_title = "CastStudio"
    @jnlp_heap_size = "128m"
    @jnlp_vendor    = "nishimotz"
    headers['Content-Type'] = 'application/x-java-jnlp-file'
  end
  
  def index
    episode_id = params[:episode_id]
    episode = Episode.find(episode_id)
    @title = episode.title
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
