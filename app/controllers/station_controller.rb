class StationController < ApplicationController
  def index
    @stations = Station.find :all
    expire_page :controller => 'caststudio', :action => 'run', :format => 'jnlp'
  end

  def show_station
    @station = Station.find(params[:id])
  end

end
