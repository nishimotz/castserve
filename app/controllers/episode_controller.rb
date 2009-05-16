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
end
