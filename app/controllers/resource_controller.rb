# http://groups.google.co.jp/group/comp.lang.ruby/browse_thread/thread/e4ffdc9226489861
require 'uri'
require 'lib/mmm/wave_utils'

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
    # "path/to/foobar.wav" => "foobar_org.wav"
    filename_org = File.basename(uri.path, ".*") + "_org" + File.extname(uri.path)
    filename     = File.basename(uri.path)
    begin
      http.start do
        http.request_get(uri.path) do |res|
          File.open(RAILS_ROOT + "/tmp/" + filename_org, 'wb') do |f|
            f.write(res.body)
          end
        end
      end
      WaveUtils.wav_to_linear(RAILS_ROOT + "/tmp/" + filename_org, 
                              RAILS_ROOT + "/public/audio/" + filename)
      # WaveUtils.wav_to_linear(RAILS_ROOT + "/tmp/" + filename_org, 
      #                         RAILS_ROOT + "/tmp/" + filename)
      # a = Audiofile.new
      # a.name = filename
      # a.file = File.open(RAILS_ROOT + "/tmp/" + filename, 'rb').read
      # a.save!
    rescue
      flash[:notice] = "error on saving audio."
      redirect_to :back ; return
    end
    # add to Mediaitem
    mediaitem = Mediaitem.new
    mediaitem.filepath = filename
    mediaitem.created_at = params[:import_pub_date]
    mediaitem.title = Resource.find(params[:id]).title
    mediaitem.category = 'message'
    if mediaitem.save
      mediaitem.update_shape
      flash[:notice] = "#{filename} import OK."
    else
      flash[:notice] = "#{filename} import ERROR."
    end
    redirect_to :back #redirect_to :show, :id=>params[:id]
  end
end
