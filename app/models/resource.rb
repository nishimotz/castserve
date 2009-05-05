#
# require 'rss'
# rss_source = 'http://localhost/mmm/?format=rss'
# rss = RSS::Parser.parse(rss_source, false) # false: ignore errors
# p rss.channel.title
# rss.channel.items.each do |item|
#   p item.title
# end

require 'rss'
class Resource < ActiveRecord::Base
  def last_update
    "2009-04-25 12:34:56 UTC"
  end
  
  def items
    rss = RSS::Parser.parse(location, false)
    rss.channel.items
  end
  
  def item_count
    items.length
  end
end
