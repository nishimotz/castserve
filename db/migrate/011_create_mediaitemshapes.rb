class CreateMediaitemshapes < ActiveRecord::Migration
  def self.up
    create_table :mediaitemshapes do |t|
      t.integer :mediaitem_id
      t.integer :pos
      t.integer :low_value
      t.integer :high_value

      t.timestamps
    end
  end

  def self.down
    drop_table :mediaitemshapes
  end
end
