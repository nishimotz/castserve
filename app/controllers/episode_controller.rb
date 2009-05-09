class EpisodeController < ApplicationController
  def index
    @episodes = Episode.find(:all, :conditions => {:station => session[:current_station]})
  end
  
  def show
    @episode = Episode.find_by_id(params[:id])
  end

  def new
  end

  def create
    flash[:notice] = 'new episode created'
    redirect_to :action=>:index
  end
end
