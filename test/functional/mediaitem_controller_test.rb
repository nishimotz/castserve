require File.dirname(__FILE__) + '/../test_helper'

class MediaitemControllerTest < ActionController::TestCase
  # rake db:migrate
  # rake db:test:clone
  # rake test
  def test_index
    get :index, :format => 'rss'
  end
end
