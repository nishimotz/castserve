class ResourceController < ApplicationController
  def index
    @resources = Resource.find(:all)
  end
  
  def show
    @resource = Resource.find(params[:id])
    @items = @resource.items
  end
  
  def new
    #
  end
  
  def import
    redirect_to :back #redirect_to :show, :id=>params[:id]
  end
end
