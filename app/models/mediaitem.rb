# require 'cgi'

class Mediaitem < ActiveRecord::Base
  has_and_belongs_to_many :users
  has_and_belongs_to_many :mediaiteminfo

  #def link
  #  'http://localhost:3000/caststudio/rpc'
  #end
  
  def pubdate
    # CGI::rfc1123_date(Time.new.to_s)
    Time.parse(Time.new.to_s).rfc822
  end
  
  def category
    'message'
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
      filepath = "test.wav"
      File.open("public/audio/" + filepath,"wb") do |file|
        file.write audio.read
      end
      self.filepath = filepath
    end
  end
end
