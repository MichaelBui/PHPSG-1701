package main

import (
	redis "gopkg.in/redis.v5"
	"log"
	"time"
	"encoding/json"
)

const (
	REDIS_API string = "redis:6379"
	REDIS_PASSWORD string = ""
	REDIS_DATABASE int = 0
	REDIS_SUBSCRIBED_CHANNEL string = "form.post_bind"
	REDIS_MESSAGE_LIST string = "foo-messages"
	REDIS_KEY_TIMEOUT time.Duration = 3
	REDIS_PREFIX string = "foo"
)

type Entity struct {
	ID string `json:"id"`
	Name string `json:"name"`
}

var client *redis.Client

func main() {
	client = redis.NewClient(&redis.Options{
		Addr:     REDIS_API,
		Password: REDIS_PASSWORD,
		DB:       REDIS_DATABASE,
	})
	defer client.Close()
	subscribe()
}

func subscribe() {
	pubsub, err := client.PSubscribe(REDIS_SUBSCRIBED_CHANNEL)
	if err != nil {
		panic(err)
	}
	defer pubsub.Close()

	for {
		msg, err := pubsub.ReceiveMessage()
		if err != nil {
			panic(err)
		}
		go process(msg.Payload)
	}
}

func process(msg string) {
	var entity Entity
	err := json.Unmarshal([]byte(msg), &entity)
	if err != nil {
		log.Println(err)
		return
	}

	if !isFirstRunner(entity) {
		log.Println("Not the 1st runner!")
		return
	}

	passToWorker(entity);
}

func isFirstRunner(entity Entity) bool {
	ok := client.SetNX(REDIS_PREFIX + entity.ID, 1, REDIS_KEY_TIMEOUT * time.Second).Val()
	if !ok {
		client.Expire(REDIS_PREFIX + entity.ID, REDIS_KEY_TIMEOUT * time.Second)
	}
	return ok
}

func passToWorker(entity Entity) {
	client.LPush(REDIS_MESSAGE_LIST, entity.Name)
	log.Println("Pushed", entity.Name, "to", REDIS_MESSAGE_LIST)
}
