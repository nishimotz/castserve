class CreateMediaitemsUsers < ActiveRecord::Migration
  def self.up
    create_table(:mediaitems_users, :id => false) do |t|
      t.integer :mediaitem_id
      t.integer :user_id
      t.timestamps
    end
  end

  def self.down
    drop_table :mediaitems_users
  end
end
