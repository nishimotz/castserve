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
  
  def submit_items
    if params[:add_to_channel] != nil
      add_to_channel
    end
  end

  def add_to_channel
    channel = Channel.find(params[:channel_id])
    params[:target_id].each do |id|
      mi = Mediaitem.find(id)
      begin
        # TODO: mi.channels.push channel
      rescue
        # already added
      end
    end
    flash[:notice] = 'mediaitems are added to channel.'
    redirect_to :controller=>:channel, :action=>:show, :id=>channel.id 
  end

end
