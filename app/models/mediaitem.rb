require 'uri'
require 'lib/mmm/wave_utils'

class Mediaitem < ActiveRecord::Base
  has_and_belongs_to_many :episodes
  has_and_belongs_to_many :mediaiteminfos
  has_and_belongs_to_many :channels
  has_many :mediaitemshapes

  #def link
  #  'http://localhost:3000/caststudio/rpc'
  #end
  
  def pubdate
    updated_at
  end
  
  def pubdate_rfc822
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
  
  # duplication : resource_controller.rb
  def uploaded_audio=(audio)
    # return nil if audio.respond_to?(:content_type) and audio.content_type.match(/^audio\b/)
    ext = File.extname(audio.original_filename) # ".wav" or ".mp3" ..
    tstamp = Time.now.getutc.strftime("%Y%m%d-%H%M%S") + sprintf("-%04x", rand(0x10000))
    filename_org = "mediaitem-#{tstamp}_org#{ext}"
    filename_wav = "mediaitem-#{tstamp}.wav"
    File.open("public/audio/" + filename_org, "wb") do |file|
      file.write audio.read
    end
    # convert filename_org => filename
    WaveUtils.wav_to_linear(RAILS_ROOT + "/public/audio/" + filename_org, 
                            RAILS_ROOT + "/public/audio/" + filename_wav)
    self.filepath = filename_wav
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
