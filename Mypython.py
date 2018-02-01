# -*- coding: UTF-8 -*-
import jieba
jieba.load_userdict("绝对路径/usr.dic")
……
data = json.loads(sys.argv[1])
result = core_process(data)
print result
