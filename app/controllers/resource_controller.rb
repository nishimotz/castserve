
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
    url = params[:import_url]
    mediaitem = Mediaitem.new
    begin
      mediaitem.import(url)
    rescue
      flash[:notice] = "error on saving audio."
      redirect_to :back ; return
    end
    mediaitem.created_at = params[:import_pub_date]
    mediaitem.title = Resource.find(params[:id]).title
    mediaitem.category = 'message'
    if mediaitem.save
      mediaitem.update_shape
      flash[:notice] = "#{url} import OK."
    else
      flash[:notice] = "#{url} import ERROR."
    end
    redirect_to :back
  end
end
