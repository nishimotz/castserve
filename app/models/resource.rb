class Resource < ActiveRecord::Base
  def index
    @resources = Resource.find(:all)
  end
end
