class EpisodeController < ApplicationController
  def index
    @episodes = Episode.find(:all, :conditions => {:station => session[:current_station]})
  end
  
  def show
    @episode = Episode.find_by_id(params[:id])
  end
end
