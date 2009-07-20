class CreateAudiofiles < ActiveRecord::Migration
  def self.up
    create_table :audiofiles do |t|
      t.binary :file, :limit => 1000000
      t.string :name

      t.timestamps
    end
  end

  def self.down
    drop_table :audiofiles
  end
end
