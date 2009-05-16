class StickerController < ApplicationController
  def index
    @title = Channel.find(@current_channel_id).title
    @description = 'CastStudio'
    @language = 'ja'
    @pubdate = Time.parse(Time.new.to_s).rfc822
    @ch_category = 'CastStudio'
    @ttl = 90
    
    @items = Mediaitem.find(:all, :conditions => {:channel_id => @current_channel_id, :item_type => 'sticker' })
  end
  
  def show
    @item = Mediaitem.find(params[:id])
  end
end
