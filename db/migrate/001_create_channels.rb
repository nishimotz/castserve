class CreateChannels < ActiveRecord::Migration
  def self.up
    create_table :channels do |t|
      t.string :number
      t.string :title
      t.timestamps
    end
    #    Channel.create :number => '77735', :title => '投稿'
    #    Channel.create :number => '77799', :title => 'ステッカー'
  end

  def self.down
    #    Channel.delete_all
    drop_table :channels
  end
end
