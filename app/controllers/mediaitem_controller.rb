class MediaitemController < ApplicationController
  include MediaitemHelper 
  # app/helpers/mediaitem_helper.rb

  def index
    # TODO: query by episode_id
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
    @mediaitem.category = 'message'
  end
  
  def submit_items
    if params[:add_to_episode] != nil
      add_to_episode
    end
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

end
