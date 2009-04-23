class HomeController < ApplicationController
  
  def index
    @user = session[:user]
  end

end
