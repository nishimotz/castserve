#
# require 'rss'
# rss_source = 'http://localhost/mmm/?format=rss'
# rss = RSS::Parser.parse(rss_source, false) # false: ignore errors
# p rss.channel.title
# rss.channel.items.each do |item|
#   p item.title
# end

require 'rss'
require 'timeout'
class Resource < ActiveRecord::Base
  def last_update
    Time.now # FIXME
  end
  
  def items
    begin
      timeout(1.0) do 
        rss = RSS::Parser.parse(location, false)
        return rss.channel.items
      end
    rescue TimeoutError
      return []
    end
  end
  
  def item_count
    items.length
  end
end
