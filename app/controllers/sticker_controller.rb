class StickerController < ApplicationController
  def index
    num = '77735' # default
    ch = Channel.find_by_number(num)
    @title = ch.title
    #@link = 'http://localhost:3000/caststudio/rpc'
    @description = 'CastStudio'
    @language = 'ja'
    @pubdate = Time.parse(Time.new.to_s).rfc822
    @ch_category = 'CastStudio'
    @ttl = 90
    
    @items = Mediaitem.find_all_by_station(num)
  end
  
  def show
    @item = Mediaitem.find(params[:id])
  end
end
