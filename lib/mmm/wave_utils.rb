#!/usr/bin/ruby -Ku
# -*- coding: utf-8 -*-
#
# $ sudo apt-get install sox ffmpeg
# $ ruby ./script/console 
# >> require 'lib/mmm/wave_utils.rb'
# >> WaveUtils.wav_to_shape('/tmp/hoge.wav', '/tmp/hoge.shape')

module WaveUtils
  SOX_BIN      = "/usr/bin/sox"
  FFMPEG_BIN   = "/usr/bin/ffmpeg"
  MMM_BIN_DIR  = File.dirname(__FILE__) + "/mmmpeak/"
  MMMPEAK_BIN  = MMM_BIN_DIR + "mmmpeak" 
  MMMSHAPE_BIN = MMM_BIN_DIR + "mmmshape"
  
  def self.wav_to_linear(srcfile, destfile)
    exec = "#{SOX_BIN} #{srcfile} -r 8000 -2 -c 1 #{destfile}"
    ret  = "[#{exec}]\n"
    ret += `#{exec}`
    FileUtils.chmod(0666, destfile)
    return ret
  end
  
  def self.wav_to_shape(srcfile, shapefile)
    exec = "#{SOX_BIN} #{srcfile} -r 8000 -c 1 -t sw - | #{MMMSHAPE_BIN} > #{shapefile}"
    ret  = "[#{exec}]\n"
    ret += `#{exec}`
    FileUtils.chmod(0666, shapefile)
    return ret
  end
  
  def self.wav_to_shape_array(srcfile)
    exec = "#{SOX_BIN} #{srcfile} -r 8000 -c 1 -t sw - | #{MMMSHAPE_BIN}"
    `#{exec}`.split(/\n/)
  end
  
  def self.do_mmmpeak(wavfile)
    exec = "#{SOX_BIN} #{wavfile} -t sw - | #{MMMPEAK_BIN}"
    return `#{exec}`.strip
  end
  
  def self.ffmpeg_wav_to_mp3(srcfile, mp3file, tmpfile)
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
  
  def self.ffmpeg_mp3_to_wav(srcfile, wavfile)
    ffmpeg_exec = "#{FFMPEG_BIN} -y -i #{srcfile} #{wavfile}"
    ret = "[#{ffmpeg_exec}]\n"
    ret += `#{ffmpeg_exec}`
    FileUtils.chmod(0666, wavfile)
    return ret
  end
end
