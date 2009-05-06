class AddTypeToMediaitem < ActiveRecord::Migration
  def self.up
    add_column :mediaitems, :item_type, :string
  end

  def self.down
    remove_column :mediaitems, :item_type
  end
end
