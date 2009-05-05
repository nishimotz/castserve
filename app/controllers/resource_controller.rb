# http://groups.google.co.jp/group/comp.lang.ruby/browse_thread/thread/e4ffdc9226489861
require 'uri'

class ResourceController < ApplicationController
  def index
    @resources = Resource.find(:all)
  end
  
  def show
    @resource = Resource.find(params[:id])
    @items = @resource.items
  end
  
  def new
    #
  end
  
  def import
    uri = URI.parse(params[:import_url])
    http = Net::HTTP.new(uri.host, uri.port)
    filename = File.basename(uri.path)
    http.start do
      http.request_get(uri.path) do |res|
        File.open("public/audio/" + filename, 'wb') do |f|
          f.write(res.body)
        end
      end
    end
    mediaitem = Mediaitem.new
    mediaitem.station = "77735"
    mediaitem.filepath = filename
    mediaitem.created_at = params[:import_pub_date]
    if mediaitem.save
      flash[:notice] = "#{filename} import OK."
    else
      flash[:notice] = "#{filename} import ERROR."
    end
    redirect_to :back #redirect_to :show, :id=>params[:id]
  end
end
