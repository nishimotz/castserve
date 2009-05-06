require 'time'
#require 'mmm/wave_utils'

class MediaitemController < ApplicationController
  #include WaveUtils
  
  def index
    if params[:station]
      num = params[:station]
    else 
      num = '77735' # default
    end
    #if params[:uid]
    #  uid = params[:uid]
    #else
    #  uid = '101'
    #end
    ch = Channel.find_by_number(num)
    @title = ch.title
    #@link = 'http://localhost:3000/caststudio/rpc'
    @description = 'CastStudio'
    @language = 'ja'
    @pubdate = Time.parse(Time.new.to_s).rfc822
    @ch_category = 'CastStudio'
    @ttl = 90
    
    # @items = Mediaitem.find_all_by_station(num)
    @items = Mediaitem.find(:all, :conditions => {:station => num })
    # , :item_type => 'message'
  end
  
  def new
    # 
  end
  
  def create
    @mediaitem = Mediaitem.new(params[:mediaitem])
    if @mediaitem.save
      flash[:notice] = 'create OK.'
      redirect_to :action => :show, :id => @mediaitem
    else
      flash[:notice] = 'create Error.'
      render :action => 'new'
    end
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
    redirect_to :action => :show, :id => id
  end
  
end
