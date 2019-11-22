#!/usr/bin/env python

import praw
import sys 

client_id = sys.argv[1]
client_secret = sys.argv[2]
refresh_token = sys.argv[3]
subredditName = sys.argv[4]
title = sys.argv[5]
url = sys.argv[6]

reddit = praw.Reddit(client_id = client_id,
                     client_secret = client_secret,
                     refresh_token = refresh_token,
                     user_agent='my user agent')

print(reddit.subreddit(subredditName).submit(title = title,url = url))