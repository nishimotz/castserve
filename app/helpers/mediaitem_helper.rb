module MediaitemHelper
  def update_shape
    mediaitem = Mediaitem.find_by_id(params[:id])
    mediaitem.update_shape
    flash[:notice] = 'mediaitemshape updated.'
    redirect_to :action=>:show, :id=>mediaitem.id
  end
end
