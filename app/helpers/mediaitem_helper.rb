module MediaitemHelper
  def update_shape
    mediaitem = Mediaitem.find_by_id(params[:id])
    mediaitem.update_shape
    flash[:notice] = 'mediaitemshape updated.'
    redirect_to :action=>:show, :id=>mediaitem.id
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

end
