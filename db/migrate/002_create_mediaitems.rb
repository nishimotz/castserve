class CreateMediaitems < ActiveRecord::Migration
  def self.up
    create_table :mediaitems do |t|
      t.string :station
      t.string :title
      t.string :desc
      t.string :author
      t.string :filepath
      t.timestamps
    end
    #    Mediaitem.create :station => '77735', :title => 'タイトル１', :desc => '補足１', 
    #      :author => '発言者１', :filepath => 'FAK_3Z82A.wav'
    #    Mediaitem.create :station => '77735', :title => 'タイトル２', :desc => '補足２', 
    #      :author => '発言者２', :filepath => 'FAP_43O6571A.wav'
  end

  def self.down
    #    Mediaitem.delete_all
    drop_table :mediaitems
  end
end
