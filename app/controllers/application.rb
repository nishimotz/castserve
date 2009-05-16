# Filters added to this controller apply to all controllers in the application.
# Likewise, all the methods added will be available for all controllers.

class ApplicationController < ActionController::Base
  helper :all # include all helpers, all the time

  # See ActionController::RequestForgeryProtection for details
  # Uncomment the :secret if you're not using the cookie session store
  protect_from_forgery # :secret => '6440008e7162c808f001e1d4ded5c51e'

  helper_method :logged_in?
  def logged_in?
    session[:password] == "pass"
  end

  before_filter :check_login
  def check_login
    @user = session[:user]
    @current_channel_id = session[:current_channel_id]
    @current_channel_name = Channel.find(session[:current_channel_id]).name
  end
end
