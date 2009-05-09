class CreateEpisodesMediaitems < ActiveRecord::Migration
  def self.up
    create_table(:episodes_mediaitems, :id => false) do |t|
      t.integer :episode_id
      t.integer :mediaitem_id
    end
    add_index(:episodes_mediaitems, [:episode_id, :mediaitem_id], :unique => true)
  end

  def self.down
    drop_table :episodes_mediaitems
  end
end
