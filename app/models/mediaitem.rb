# require 'cgi'
require 'lib/mmm/wave_utils'

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
    if audio.respond_to?(:content_type) and audio.content_type.match(/^audio\b/)
      tstamp = Time.now.getutc.strftime("%Y%m%d-%H%M%S") + sprintf("-%04x", rand(0x10000))
      filepath = "mediaitem-#{tstamp}.wav"
      File.open("public/audio/" + filepath,"wb") do |file|
        file.write audio.read
      end
      self.filepath = filepath
    end
  end

  def update_shape
    Mediaitemshape.delete_all :mediaitem_id=>self.id
    ar = WaveUtils.wav_to_shape_array(RAILS_ROOT + '/public/audio/' + self.filepath)
    ar.each do |i|
      f = i.split(/ /)
      if f.size == 3
        shape = Mediaitemshape.new
        shape.mediaitem_id = self.id
        shape.pos, shape.low_value, shape.high_value = f[0].to_i, f[1].to_i, f[2].to_i
        shape.save!
      end
    end
  end
end
