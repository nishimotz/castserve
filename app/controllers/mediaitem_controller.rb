require 'time'
#require 'mmm/wave_utils'

class MediaitemController < ApplicationController
  #include WaveUtils
  
  # from Web Browser : http://ubuntu-vm:3000/mediaitem/
  # TODO: query by episode_id
  def index
    @items = Mediaitem.find(:all, :conditions => {:item_type => 'message' })
    # for radio_button_tag
    @episodes = Episode.find(:all, :conditions => {:station=>@current_station})
    # @episodes = Episode.find(:all)
    @episodes.each_with_index do |i, idx|
      def i.selected=(b)
        @selected = b
      end
      def i.selected?
        @selected
      end
      # TODO: station should remember default episode
      i.selected = false
      i.selected = true if idx == (@episodes.length - 1)
    end
    # for check_box_tag
    # TODO: show items registered already 
    @target = {}
    @items.each do |i|
      @target[i.id] = false
    end
  end
  
  def new
    @mediaitem = Mediaitem.new
    @mediaitem.station = @current_station
    @mediaitem.category = 'message'
  end
  
  def create
    @mediaitem = Mediaitem.new(params[:mediaitem])
    if @mediaitem.save
      flash[:notice] = 'create OK.'
      redirect_to :action => :show, :id => @mediaitem
    else
      flash[:notice] = 'create Error.'
      render :action => 'new'
    end
  end
  
  def show
    @item = Mediaitem.find(params[:id])
  end
  
  def edit
    @item = Mediaitem.find(params[:id])
  end
  
  def update
    id = params[:id]
    @item = Mediaitem.find_by_id(id)
    @item.update_attributes(params[:item])
    flash[:notice] = 'update OK.'
    redirect_to :action => :show, :id => id
  end

  def add_to_episode
    episode = Episode.find(params[:episode_id])
    params[:target_id].each do |id|
      mi = Mediaitem.find(id)
      begin
        mi.episodes.push episode
      rescue
        # already added
      end
      #mi.save!
      #ep = Episode.find(params[:episode_id])
      #ep.mediaitem_ids.push episode_id 
      #ep.save!
    end
    flash[:notice] = 'mediaitems are added to episode.'
    redirect_to :controller=>:episode, :action =>:show, :id=>episode.id 
  end
  
end
