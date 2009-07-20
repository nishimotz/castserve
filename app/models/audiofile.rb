class Audiofile < ActiveRecord::Base
  def file_size
    file.size
  end
end
