#
# $ sudo apt-get install sox ffmpeg
# $ ruby ./script/console 
# >> m = MediaitemController.new
# >> m.wav_to_shape('/tmp/hoge.wav', '/tmp/hoge.shape')

module WaveUtils
  #puts 'hoge'  
  SOX_BIN      = "/usr/bin/sox"
  FFMPEG_BIN   = "/usr/bin/ffmpeg"
  MMM_BIN_DIR  = File.dirname(__FILE__) + "/mmmpeak/"
  MMMPEAK_BIN  = '"' + MMM_BIN_DIR.gsub(/ /, '¥ ') + "mmmpeak"  + '"'
  MMMSHAPE_BIN = '"' + MMM_BIN_DIR.gsub(/ /, '¥ ') + "mmmshape" + '"'
  
  def wav_to_shape(srcfile, shapefile)
    exec = "#{SOX_BIN} #{srcfile} -r 8000 -c 1 -t sw - | #{MMMSHAPE_BIN} > #{shapefile}"
    ret  = "[#{exec}]\n"
    ret += `#{exec}`
    FileUtils.chmod(0666, shapefile)
    return ret
  end
  
  def do_mmmpeak(wavfile)
    exec = "#{SOX_BIN} #{wavfile} -t sw - | #{MMMPEAK_BIN}"
    return `#{exec}`.strip
  end
  
  def ffmpeg_wav_to_mp3(srcfile, mp3file, tmpfile)
    sox_exec = "#{SOX_BIN} #{srcfile} -r 44100 #{tmpfile}"
    ret  = "[#{sox_exec}]\n"
    ret += `#{sox_exec}`
    FileUtils.chmod(0666, tmpfile)
    ffmpeg_exec = "#{FFMPEG_BIN} -y -i #{tmpfile} #{mp3file}"
    ret += "[#{ffmpeg_exec}]\n"
    ret += `#{ffmpeg_exec}`
    FileUtils.chmod(0666, mp3file)
    return ret
  end
  
  def ffmpeg_mp3_to_wav(srcfile, wavfile)
    ffmpeg_exec = "#{FFMPEG_BIN} -y -i #{srcfile} #{wavfile}"
    ret = "[#{ffmpeg_exec}]\n"
    ret += `#{ffmpeg_exec}`
    FileUtils.chmod(0666, wavfile)
    return ret
  end
end
