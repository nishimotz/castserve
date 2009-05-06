# This file is auto-generated from the current state of the database. Instead of editing this file, 
# please use the migrations feature of ActiveRecord to incrementally modify your database, and
# then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your database schema. If you need
# to create the application database on another system, you should be using db:schema:load, not running
# all the migrations from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 9) do

  create_table "channels", :force => true do |t|
    t.string   "number"
    t.string   "title"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "episodes", :force => true do |t|
    t.string   "title"
    t.string   "station"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "mediaiteminfos", :force => true do |t|
    t.integer  "mediaitem_id"
    t.integer  "user_id"
    t.integer  "color"
    t.float    "media_start_time"
    t.float    "media_stop_time"
    t.integer  "pos_x"
    t.integer  "pos_y"
    t.integer  "z_order"
    t.boolean  "fetched"
    t.string   "container"
    t.float    "gain"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "mediaitems", :force => true do |t|
    t.string   "station"
    t.string   "title"
    t.string   "desc"
    t.string   "author"
    t.string   "filepath"
    t.datetime "created_at"
    t.datetime "updated_at"
    t.string   "item_type"
  end

  create_table "mediaitems_users", :id => false, :force => true do |t|
    t.integer  "mediaitem_id"
    t.integer  "user_id"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "resources", :force => true do |t|
    t.string "title"
    t.string "location"
  end

  create_table "stations", :force => true do |t|
    t.string   "number"
    t.string   "title"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "users", :force => true do |t|
    t.string   "name"
    t.string   "password"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

end
