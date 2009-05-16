class ChannelController < ApplicationController
  def index
    @channels = Channel.find :all
    expire_page :controller => :caststudio, :action => :run, :format => :jnlp
  end

  def show
    @channel = Channel.find(params[:id])
    session[:current_channel_id] = @current_channel_id = @channel.id
    @current_channel_name = @channel.name
  end

end
