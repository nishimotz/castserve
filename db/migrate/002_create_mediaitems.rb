class CreateMediaitems < ActiveRecord::Migration
  def self.up
    create_table :mediaitems do |t|
      t.integer :channel_id
      t.string :title
      t.string :desc
      t.string :author
      t.string :filepath
      t.timestamps
    end
  end

  def self.down
    drop_table :mediaitems
  end
end
