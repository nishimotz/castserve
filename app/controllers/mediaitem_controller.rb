require 'time'

class MediaitemController < ApplicationController
  include MediaitemHelper # app/helpers/mediaitem_helper.rb

  # from Web Browser : http://ubuntu-vm:3000/mediaitem/
  # TODO: query by episode_id
  def index
    @items = Mediaitem.find(:all, :conditions => {:item_type => 'message' })
    # for radio_button_tag
    @episodes = Episode.find(:all, :conditions => {:channel_id=>@current_channel_id})
    # @episodes = Episode.find(:all)
    @episodes.each_with_index do |i, idx|
      def i.selected=(b)
        @selected = b
      end
      def i.selected?
        @selected
      end
      # TODO: channel should remember default episode(?)
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
    # @mediaitem.station = @current_station
    @mediaitem.category = 'message'
  end
  
  def create
    @mediaitem = Mediaitem.new(params[:mediaitem])
    if @mediaitem.save
      @mediaitem.update_shape
      flash[:notice] = 'create OK.'
      redirect_to :action=>:show, :id=>@mediaitem
    else
      flash[:notice] = 'create Error.'
      redirect_to :action=>:new
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
    redirect_to :action=>:show, :id=>id
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
    end
    flash[:notice] = 'mediaitems are added to episode.'
    redirect_to :controller=>:episode, :action=>:show, :id=>episode.id 
  end

#  def update_shape
#    mediaitem = Mediaitem.find_by_id(params[:id])
#    mediaitem.update_shape
#    flash[:notice] = '(dummy) mediaitemshape updated.'
#    redirect_to :action=>:show, :id=>mediaitem.id
#  end

end
