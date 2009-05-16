class Episode < ActiveRecord::Base
  has_and_belongs_to_many :mediaitems
  belongs_to :channel
end
