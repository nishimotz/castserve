# require 'cgi'

class Mediaitem < ActiveRecord::Base
  has_and_belongs_to_many :episodes
  has_and_belongs_to_many :mediaiteminfos
  has_many :mediaitemshapes

  #def link
  #  'http://localhost:3000/caststudio/rpc'
  #end
  
  def pubdate
    # CGI::rfc1123_date(Time.new.to_s)
    #Time.parse(Time.new.to_s).rfc822
    updated_at.rfc822
  end
  
  def category
    item_type # 'message'
  end
  
  def category=(c)
    self.item_type = c
  end
  
  def filename_without_ext
    filepath.gsub(/\.wav$/, '')
  end
  
  def filetype
    'audio/x-wav'
  end
  
  def uploaded_audio=(audio)
    if audio.respond_to?(:content_type) and
      audio.content_type.match(/^audio\b/)
      tstamp = Time.now.getutc.strftime("%Y%m%d-%H%M%S") + sprintf("-%04x", rand(0x10000))
      filepath = "mediaitem-#{tstamp}.wav"
      File.open("public/audio/" + filepath,"wb") do |file|
        file.write audio.read
      end
      self.filepath = filepath
    end
  end
end
