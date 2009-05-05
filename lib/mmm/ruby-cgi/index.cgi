#!/usr/bin/ruby -Ku
# coding: utf-8
# $Id: index.cgi,v 1.5 2009/04/27 07:55:32 nishi Exp $
# (c) Takuya NISHIMOTO 
#
# [setup]
# $ sudo apt-get install sox libsox-fmt-all
# $ mkdir msg; chmod 777 msg

require 'cgi'
require 'erb'

class View
  attr_accessor :c # controller
  def initialize(c)
    @c = c
  end
  
  def translate(src)
    ERB.new(src, nil, '-').result(binding)
  end
end

class VxmlView < View
  def length_wave_time
    sec = @c.media_time
    f = "t1005"
    if sec < 6
      f = "t1005"
    elsif sec < 11
      f = "t1010"
    elsif sec < 21
      f = "t1020"
    elsif sec < 31
      f = "t1030"
    elsif sec < 41
      f = "t1040"
    elsif sec < 51
      f = "t1050"
    elsif sec < 61
      f = "t1060"
    elsif sec < 71
      f = "t1070"
    elsif sec < 81
      f = "t1080"
    elsif sec < 91
      f = "t1090"
    elsif sec < 101
      f = "t1100"
    elsif sec < 111
      f = "t1110"
    elsif sec < 121
      f = "t1120"
    elsif sec < 131
      f = "t1130"
    else
      f = "t1140"
    end
    "./wav/ja/#{f}.wav"
  end

  def greeting_document
    return (<<"EOD") 
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="greeting">
<var name="format"   expr="'vxml'"/>
<var name="nextdoc"  expr="'recording_start'"/>
<var name="phonenum" expr="session.telephone.ani"/>
<var name="datetime" expr="'#{@c.datetime}'"/>
<block>
 <submit next="#{@c.self_url}"
  method="post"
  namelist="format nextdoc phonenum datetime"/>
</block>
</form>
</vxml>
EOD
  end

  def recording_start_document
    return (<<"EOD") 
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="recording_start">
<var name="format"   expr="'vxml'"/>
<var name="nextdoc"  expr="'recording_end'"/>
<var name="phonenum" expr="'#{@c.phonenum}'"/>
<var name="datetime" expr="'#{@c.datetime}'"/>
<var name="rectake"  expr="'#{@c.rectake}'"/>
<var name="create"   expr="'1'"/>
<block>
 <prompt bargein="false">
 <audio src="./wav/ja/m106.wav"/> <!-- korekara anata no -->
 <audio src="./wav/ja/m107.wav"/> <!-- shaberi owattara sharp wo oshite kudasai -->
 take #{@c.rectake}
 <audio src="./wav/ja/m108.wav"/> <!-- dewa rokuon shimasu, 3,2,1 -->
 </prompt>
</block>
<record name="msg" 
 type="audio/x-wav" beep="true" dtmfterm="true"
 maxtime="140s" finalsilence="10s" >
</record>
<block>
 <prompt bargein="false">
 <audio src="./wav/ja/sin.wav"/>
 </prompt>
</block>
<block>
 <submit next="#{@c.self_url}"
  method="post"
  namelist="format nextdoc phonenum datetime rectake create msg"/>
</block>
</form>
</vxml>
EOD
  end
  
  def recording_end_document
    if @c.clip_count > 1
      comment_vxml = '<audio src="./wav/ja/m018.wav"/><audio src="./wav/ja/m033.wav"/>'
      # <!-- otoga ookisugi masu --><!-- juwaki ni chikazuki suginaide -->
    else
      comment_vxml = '<audio src="./wav/ja/m017.wav"/>'
      # <!-- otsukare sama deshita -->
    end
    return (<<"EOD") 
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="recording_end">
<property name="inputmodes" value="dtmf" /> 
<var name="format"   expr="'vxml'"/>
<var name="nextdoc"  expr="'recording_start'"/>
<var name="phonenum" expr="'#{@c.phonenum}'"/>
<var name="datetime" expr="'#{@c.datetime}'"/>
<var name="rectake"  expr="'#{@c.rectake}'"/>
<field name="cmd" type="digits?length=1">
 <prompt bargein="true" timeout="10s">
  <audio src="./wav/ja/ok.wav"/>
  <audio src="./wav/ja/m016.wav"/> <!-- anata no rokuon ga touroku saremashita -->
  <audio src="./wav/ja/m010.wav"/> <!-- saisei shimasu -->
  <audio src="./wav/ja/sin.wav"/>  
  <audio src="#{@c.http_wave_file}"/>
  <audio src="./wav/ja/sin.wav"/>  
  <audio src="./wav/ja/t1002.wav"/> <!-- rokuon jikan wa -->
  <audio src="#{@length_wave_file}"/> <!-- yaku xxx deshita -->
  #{comment_vxml}
  <audio src="./wav/ja/m109.wav"/> <!-- torinaosu tokiwa sharp wo .. -->
  <audio src="./wav/ja/wait.wav"/>
 </prompt>
 <catch event="nomatch"><assign name="cmd" expr="'nomatch'"/></catch>
 <catch event="noinput"><assign name="cmd" expr="'noinput'"/></catch>
 <catch event="help"><assign name="cmd" expr="'help'"/></catch>
</field>
<block>
 <audio src="./wav/ja/ok.wav"/>   
 <submit next="#{@c.self_url}"
  method="post"
  namelist="format nextdoc phonenum datetime rectake cmd"/>
</block>
</form>
</vxml>
EOD
  end
  
  def header
    "text/plain" #@c.cgi.header("text/plain")
  end
  
  def document
    case @c.nextdoc
    when 'greeting'
      return greeting_document
    when 'recording_start'
      return recording_start_document
    when 'recording_end'
      return recording_end_document
    end
  end
end

class HtmlView < View
  def header
    "text/html"
  end
  
  def greeting_document
    return (<<"EOD")
<html>
<body>
<h2>upload file</h2>
<form method="post" enctype="multipart/form-data" action="#{@c.self_url}">
<input type="hidden" name="format" value="html">
<input type="hidden" name="nextdoc" value="create">
<input type="hidden" name="create" value="1">
File Name : <input type="file" name="msg"> 
<input type="submit" value="do upload">
</form>
</body>
</html>
EOD
  end
  
  def create_document
    return (<<"EOD")
<html>
<body>
<h2>upload file ok</h2>
<a href="#{@c.http_wave_file}">[audio]</a>
<a href="#{@c.self_url}?format=html">[restart]</a>
<p>file_info: #{@c.file_info}</p>
</body>
</html>
EOD
  end

  def document
    case @c.nextdoc
    when 'greeting'
      greeting_document
    when 'create'
      create_document
    end
  end
end

# require 'rss'
# rss_source = 'http://localhost/mmm/?format=rss'
# rss = RSS::Parser.parse(rss_source, false) # false: ignore errors
# p rss.channel.title
# rss.channel.items.each do |item|
#   p item.title
# end
class RssView < View
  attr_accessor :items
  def header
    "text/xml"
  end
  
  def filename_to_time(f)
    # msg20090421-204647-5af7_rev1.wav
    m = /.*msg(\d{4})(\d{2})(\d{2})\-(\d{2})(\d{2})(\d{2}).*/.match(f)
    #m.to_a.each {|i| i = i.to_i }
    # Time.gm(year[, mon[, day[, hour[, min[, sec[, usec]]]]]])
    Time.gm(m[1].to_i, m[2].to_i, m[3].to_i, m[4].to_i, m[5].to_i, m[6].to_i)
    #t.year, t.month, t.day, t.hour, t.min, t.sec = 
    #t
  end
  
  def document
    @items = []
    ar = Dir.new(@c.msg_local_dir).to_a.select{|i| i != '.' and i != '..'}.sort{|a,b| b <=> a}
    ar.each do |i|
      @items << { 
        'enclosure_url' => @c.msg_url_prefix + i, 
        'pubDate' => CGI::rfc1123_date(filename_to_time(i)) }
    end
    s = translate(<<"EOS")
<rss version="2.0">
<channel>
<title></title>
<link></link>
<description></description>
<language></language>
<pubDate><%= CGI::rfc1123_date(Time.now.getutc) %></pubDate>
<category></category>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<% @items.each do |i| -%>
<item>
<title></title>
<link></link>
<description></description>
<pubDate><%= i['pubDate'] %></pubDate>
<author></author>
<category></category>
<enclosure url="<%= i['enclosure_url'] %>" length="0" type="audio/x-wav" />
</item>
<% end -%>
</channel>
</rss>
EOS
    s
  end
end

class Controller
  attr_accessor :cgi, :self_url, :bin_dir
  attr_accessor :nextdoc, :datetime, :phonenum, :create, :rectake # :action, 
  attr_accessor :bin_dir, :clip_count, :media_time, :http_wave_file #, :comment_vxml
  attr_accessor :original_filename, :file_extname
  attr_accessor :file_info
  attr_accessor :msg_url_prefix, :msg_local_dir
  
  def initialize
    @cgi = CGI.new
    @self_url = 'index.cgi'
    @msg_url_prefix = 'http://hil.t.u-tokyo.ac.jp/~nishi/mmm/ruby-cgi/msg/'
    @msg_local_dir = '/home/nishi/public_html/mmm/ruby-cgi/msg'
    @bin_dir = '/home/nishi/bin'
  end
  
  def session_value(name, defval)
    if @cgi.multipart?
      val = @cgi[name].read
    else      
      val = @cgi[name]
    end
    return defval if val == nil or val == ''
    val
  end
  
  def inside_tag(s)
    /\<\w+\>([\d\.]+)\<\/\w+\>/.match(s)[1]
  end
  
  def do_peak(wavfile)
    # TODO: handle stderr message (currently appeared at apache2 errors.log)
    @clip_count = 0
    @media_time = 0
    begin
      exec = "/usr/bin/sox #{wavfile} -c 1 -r 8000 -t sw - | #{@bin_dir}/mmmpeak"
      lines = `#{exec}`.split(/\n/) 
      s = lines.find { |x| x.match(/^\<clipCount\>/) }
      @clip_count = inside_tag(s).to_f
      s = lines.find { |x| x.match(/^\<mediaTime\>/) }
      @media_time = inside_tag(s).to_f
    rescue
      #
    end
  end
  
  def create_file
    if @cgi.multipart? and @cgi['msg'].respond_to? :read
      @original_filename = @cgi['msg'].original_filename
      raise "CGI submit with empty file" if @original_filename == ''
      ext = File.extname(@original_filename) #=> ".wav"
      file_name = "msg#{@datetime}_rev#{@rectake}#{ext}"
      local_wave_file = "./msg/#{file_name}"
      @http_wave_file = "./msg/#{file_name}"
      msg = @cgi['msg'].read
    else
      raise "CGI submit is not multipart"
    end
    File.open(local_wave_file, 'wb') { |f| f.write msg }
    File.chmod(0666, local_wave_file)
    do_peak(local_wave_file)
    @file_info = "#{@original_filename} #{@http_wave_file}"
    nil
  end
  
  def response
    begin
      @format   = session_value('format', 'html')
      @datetime = session_value('datetime', 
        Time.now.getutc.strftime("%Y%m%d-%H%M%S") + sprintf("-%04x", rand(0x10000)))
      @nextdoc  = session_value('nextdoc', 'greeting')
      @phonenum = session_value('phonenum', '0')
      @rectake  = (session_value('rectake', '0').to_i + 1).to_s
      @cmd      = session_value('cmd', '#')
      @create   = session_value('create', '0').to_i
      # action
      create_file if @create == 1
      # view
      case @format
      when 'html'
        view = HtmlView.new(self)
      when 'vxml'
        view = VxmlView.new(self)
      when 'rss'
        view = RssView.new(self)
      else  
        raise "format=#{@format}"
      end
      #puts view.header
      #puts view.document
      @cgi.out("type" => view.header, "encoding" => "utf-8") { view.document }
    rescue StandardError => e
      @cgi.out("type" => "text/plain", "encoding" => "utf-8") { "error: #{e.to_s}" }
    end
  end
end

if __FILE__ == $0
  Controller.new.response
end
