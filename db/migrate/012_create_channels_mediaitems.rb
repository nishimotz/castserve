class CreateChannelsMediaitems < ActiveRecord::Migration
  def self.up
    create_table :channels_mediaitems, :id => false do |t|
      t.integer :channel_id
      t.integer :mediaitem_id
      t.timestamps
    end
  end

  def self.down
    drop_table :channels_mediaitems
  end
end
