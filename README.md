	python进行科学计算很方便，因为存在大量的包，进行机器学习和自然语言处理一般采用python，今天就简单讲下用php调用python
时遇到的坑，以此做下总结。


	首先我使用python2.7（本来想使用python3的，但项目环境是2.7，假装难受一下~），使用python写好了NLP处理程序，
而且经过很多测试，也加入了很多功能。第一次用php调用python，自认为php调用python很简单，网上随便搜了下，找到一个网站
http://www.zongk.com/zongk/95.html，发现三个函数：

	1）string exec ( string $command [, array &$output [, int &$return_var ]] )
	2）string exec ( string $command [, array &$output [, int &$return_var ]] )
	3）void passthru ( string $command [, int &$return_var ] )
	
具体用法自己点进去看下就好，我就不再赘述，但是我使用成功的是第一个exec，其他两个（嘿嘿，其实是三个，还有个
shell_exec）没用成功.


	exec函数在php脚本里使用，贴一下我的代码：
exec("absolute_path/python2.7  absolute_path/Mypython.py ". escapeshellarg(json_encode($sentence)). " 2>&1",$resultData,$ret);
【注意里边的空格】，需要注意的地方：
				(1)由于我的php是在linux服务器里，absolute_path/python2.7、absolute_path/Mypython.py以及python里的路径都要使用
					绝对路径
					
				(2)这里的$sentence是我要传进python脚本里的参数，里边可能含有'>'、'<'等符号，若是直接传进去，php的exec会把它当成
					命令，于是输出就有问题了，我的python脚本里原来的很多功能都不能使用，经过各种推测，才发现的这个问题，于是需要把
					传入python里的参数使用某种编码传过去，然后在python里接收，然后再解码，就可以安全的传参了。
					这就是escapeshellarg(json_encode($sentence))的作用，这里要说明以下，若$sentence里仅仅包含普通的中文或英文、
					阿拉伯数字等没有问题，也不需要使用escapeshellarg(json_encode($sentence))，若包含大于（>）、小于号（<）等，
					就需要使用json编码，以避免被当成一般的命令。
					
				(3)2>&1的作用是将出错信息也输出，为调试提供点信息，否则在php调用python时没有提示错误，将很难debug。
				
				(4)数组$resultData用来存储返回的结果，包含python运行过程全部的输出，比如print的，在这里我只需要$resultData最后一个输出：
					$res=end($resultData);	
					
				(5)$ret返回是否调用成功，0代表成功,1代表失败
				
				
				(6)python脚本在linux命令行里可以运行（多么精确完美的运行），然而在php调用python时竟然会报找不到包的错误，网上也找了很多
				    方法，但都没用，有种走到穷途末路的感觉，第二天师兄建议我可以把找不到的包手动导进python项目目录里，然后手动import，
					最后成功了，其实这种方法很简单，但是平常使用anaconda，pip安装包已经习惯，以致淡忘了import的本质，终究手动导入还是
					有效果的，诸君也要记得这种方法。
	
代码片段：
php部分脚本：
	exec("absolute_path/python2.7  absolute_path/Mypython.py ". escapeshellarg(json_encode($sentence)). " 2>&1",$resultData,$ret);
	$res=end($resultData);//从$resultData取得我想要的信息

python部分脚本：
	data = json.loads(sys.argv[1])  # 取得php传过来的参数，并进行json解码
	sql_list = core_process(data)  # 使用取到的数据进行运算
	
	总结下，php调用python坑真的很多，这话不虚，但我还是成功了，诸君共勉！

	
