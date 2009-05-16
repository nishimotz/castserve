class StickerController < ApplicationController
  include MediaitemHelper
  def index
    @title = Channel.find(@current_channel_id).title
    @description = 'CastStudio'
    @language = 'ja'
    @pubdate = Time.parse(Time.new.to_s).rfc822
    @ch_category = 'CastStudio'
    @ttl = 90
    
    @items = Mediaitem.find(:all, :conditions => {:item_type => 'sticker' })
  end
  
  def show
    @item = Mediaitem.find(params[:id])
  end

  def new
    @mediaitem = Mediaitem.new
    @mediaitem.category = 'sticker'
  end
  
end
