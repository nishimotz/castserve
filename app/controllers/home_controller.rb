class HomeController < ApplicationController
  
  def index
    # @user = session[:user]
    redirect_to :controller=>:channel
  end

end
