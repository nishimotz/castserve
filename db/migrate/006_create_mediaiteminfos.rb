class CreateMediaiteminfos < ActiveRecord::Migration
  def self.up
    create_table :mediaiteminfos do |t|
      t.integer :mediaitem_id
      t.integer :episode_id
      t.integer :color
      t.float :media_start_time
      t.float :media_stop_time
      t.integer :pos_x
      t.integer :pos_y
      t.integer :z_order
      t.integer :fetched
      t.string :container
      t.float :gain
      t.timestamps
    end
  end

  def self.down
    drop_table :mediaiteminfos
  end
end
