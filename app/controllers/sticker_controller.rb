class StickerController < ApplicationController
  include MediaitemHelper 
  # app/helpers/mediaitem_helper.rb

  def index
    @channel_title = Channel.find(@current_channel_id).title
    @items = Mediaitem.find(:all, :conditions => {:item_type => 'sticker' })
    @channels = Channel.find(:all)
    @channels.each_with_index do |i, idx|
      def i.selected=(b)
        @selected = b
      end
      def i.selected?
        @selected
      end
      i.selected = false
      i.selected = true if i.id == @current_channel_id
    end
    @target = {}
    @items.each do |i|
      @target[i.id] = false
    end
  end
  
  def show
    @item = Mediaitem.find(params[:id])
  end

  def new
    @mediaitem = Mediaitem.new
    @mediaitem.category = 'sticker'
  end
  
end
