class EpisodeController < ApplicationController
  def index
    @episodes = Episode.find(:all, :conditions => {:channel_id => session[:current_channel_id]})
  end
  
  def show
    @episode = Episode.find_by_id(params[:id])
    @mediaitems = @episode.mediaitems
  end

  def new
    @item = Episode.new
    @item.channel_id = session[:current_channel_id]
  end

  def create
    @item = Episode.new(params[:item])
    if @item.save
      flash[:notice] = 'new episode created'
      redirect_to :action=>:index
    else
      flash[:notice] = 'create error.'
      render :action => 'new'
    end
  end

  def submit_items
    if params[:remove_from_episode] != nil
      remove_from_episode
    end
  end

  def remove_from_episode
    episode = Episode.find(params[:episode_id])
    params[:target_id].each do |id|
      mi = Mediaitem.find(id)
      begin
        mi.episodes.delete episode
      rescue
        # already removed
      end
    end
    flash[:notice] = 'mediaitems are removed from episode.'
    redirect_to :controller=>:episode, :action=>:show, :id=>episode.id 
  end

end
