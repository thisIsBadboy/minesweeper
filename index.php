<head>
<title>Minesweeper</title>
	<style type="text/css">
		.box1,.box2{
			width : 20px;
			height : 20px;
			float : left;
			border: 1px;
			margin: 0px;
		}
	</style>
</head>

<body oncontextmenu="return false;">

<div style="text-align:center;">
	<div style="display:inline-block;">
		<script type="text/javascript">
		
			var makeRoom = function(r,c)
			{
				document.write('<img src="images/bordertl.gif" height="10px" width="10px" alt="" name="bordertl" />');
				
				for (var j=0; j<c; j++) 
					  document.write('<img src="images/bordertb.gif" height="10px" width="20px" alt="" />'); 
				
				document.writeln('<img src="images/bordertr.gif" height="10px" width="10px" alt="" <br/>');
				document.write("<div style='clear:both'></div>");
				for(var i = 0;i<r;i++)
				{
					document.write("<img style='float:left' height='20px' width='10px' src='images/borderlr2.gif'/>");
					for(var j=0;j<c;j++)
					{
						document.write("<div class='box1' value='' title='' id= "+i+','+j+"><img src='images/blank.gif' title='' height='20px' width='20px' id = im" + i +','+ j +" /></div>");
					}
					document.write("<img height='20px' width='10px' src='images/borderlr2.gif'/>");
					document.write("<div style='clear:both'></div>");
				}

				document.write('<img src="images/borderbl.gif" height="10px" width="10px" alt="" />');
				
				for (var j=0; j<c; j++) 
					 document.write('<img src="images/bordertb.gif" height="10px" width="20px" alt="" />'); 

				document.writeln('<img src="images/borderbr.gif" height="10px" width="10px" alt="" /><br />');
			}
			
			var len = 0;
			var mineX=[];
			var mineY=[];
			
			var createRandomValue = function(r,c,mines)
			{
				while(len != mines)
				{
					var rNo = Math.floor(Math.random()*r);
					var cNo = Math.floor(Math.random()*c);
					if(document.getElementById(rNo+','+cNo).className == 'box1')
					{
						mineX[len]=parseInt(rNo);
						mineY[len]=parseInt(cNo);
						len++;
						document.getElementById(rNo+','+cNo).className='box2';
					}
				}
			}
			
			var replaceImage = function(name,id,title,cla)
			{
				var img = document.createElement('img');
				img.setAttribute('src', 'images/'+name+'.gif');
				img.setAttribute('width', '20px');
				img.setAttribute('height', '20px');
				img.setAttribute('id','im'+id);
				img.setAttribute('title',title);
				img.setAttribute('class',cla);
				document.getElementById(id).removeChild(document.getElementById('im'+id));
				document.getElementById(id).appendChild(img);
			}
			
			var rows,cols,mines;
			do{
				var hold = 0;
				rows = prompt('ENTER ROW NUMBER (4<=ROWS<=25) :');
				cols = prompt('ENTER COLUMN NUMBER (4<=COLS<=30) :');
				
				var total = Math.floor((parseInt(rows)*parseInt(cols))/5);
				mines = prompt('ENTER MINES NUMBER (1<=MINES<='+total+') :');
				
				if(parseInt(rows)>=4 && parseInt(cols)>=4 && parseInt(rows)<=25 && parseInt(cols)<=30 && parseInt(mines)>0 && parseInt(mines)<=total)
					hold = 1;
					
				else
					alert("AGAIN GIVE INPUT");
			}while(hold != 1);
			
			makeRoom(parseInt(rows),parseInt(cols));
			createRandomValue(parseInt(rows),parseInt(cols),parseInt(mines));
			
			
			var sR=[-1,-1,-1,0,0,1,1,1];
			var sC=[-1,0,1,-1,1,-1,0,1];
			var countLeftButtonClick = 0, countRightButtonClick=0, flagCount = 0, bombCount = 0, countBlankBox=0;
			
			for(var i=0;i<parseInt(rows);i++)
			{
				for(var  j=0;j<parseInt(cols);j++)
				{
					document.getElementById(i+','+j).onmousedown = function(event)
					{
						if(event.button == 2)
						{
							countRightButtonClick ++;
							
							if(document.getElementById('im'+this.id).title != 'FLAG')
							{
								flagCount++;
								if(this.className == 'box2')
									bombCount ++;
								
								if(document.getElementById('im'+this.id).className != 'blank')
									replaceImage('bombflagged',this.id,'FLAG','');
							}
							
							else
							{
								flagCount--;
								
								if(this.className == 'box2')
									bombCount--;

								replaceImage('blank',this.id,'','');
							}
						}

						if(event.button == 0)
						{
							if(document.getElementById('im'+this.id).title != 'FLAG')
							{
								countLeftButtonClick++;
													
								if(this.className=='box2')
								{
									if(countLeftButtonClick == 1)
									{
										this.className = 'box1';
										
										while(len != parseInt(mines)+1)
										{
											var rNo = Math.floor(Math.random()*parseInt(rows));
											var cNo = Math.floor(Math.random()*parseInt(cols));
											if(document.getElementById(rNo+','+cNo).className == 'box1')
											{
												mineX[len]=parseInt(rNo);
												mineY[len]=parseInt(cNo);
												len++;
												document.getElementById(rNo+','+cNo).className='box2';
											}
										}
									}

									else
									{
										for(var k=0;k<len;k++)
										{
											if(document.getElementById(mineX[k]+','+mineY[k]).className=='box2')
												replaceImage('bombrevealed',mineX[k]+','+mineY[k],'MINE','');
										}
										var a = alert("YOU FAIL!");
										location.reload();
										
									}
								}

								if(this.className == 'box1' && document.getElementById('im'+this.id).className != 'blank')
								{
									var info = this.id.split(",");
									
									var count;
									var queue=[];
									
									queue.push(parseInt(info[0]));
									queue.push(parseInt(info[1]));
			
									while(queue.length != 0)
									{	
										countBlankBox++;
										var value1 = queue.shift();
										var value2 = queue.shift();
										
										document.getElementById(value1+','+value2).value='checked';
										
										count = 0;
										
										for(var k=0;k<8;k++)
										{
											var first = value1 + sR[k];
											var second = value2 + sC[k];
											
											if(first>=0 && second>=0 && first<parseInt(rows) && second <parseInt(cols) && document.getElementById(first +','+ second).className == 'box2')
											{
												count++;
											}
										}
										
										if(count == 0)
										{
											for(var k=0;k<8;k++)
											{
												var first = value1 + sR[k];
												var second = value2 + sC[k];
												
												if(first>=0 && second>=0 && first<parseInt(rows) && second<parseInt(cols) && document.getElementById(first+','+second).value != 'checked' && document.getElementById('im'+first+','+second).title != 'FLAG')
												{
													queue.push(first);
													queue.push(second);
													document.getElementById(first+','+second).value = 'checked';
												}
											}

											replaceImage('open0',value1+','+value2,'','blank');
										}
										
										if(count == 1)
											replaceImage('open1',value1+','+value2,'','blank');
										
										if(count == 2)
											replaceImage('open2',value1+','+value2,'','blank');
										
										if(count == 3)
											replaceImage('open3',value1+','+value2,'','blank');
										
										if(count == 4)
											replaceImage('open4',value1+','+value2,'','blank');
										
										if(count == 5)
											replaceImage('open5',value1+','+value2,'','blank');
										
										if(count == 6)
											replaceImage('open6',value1+','+value2,'','blank');
										
										if(count == 7)
											replaceImage('open7',value1+','+value2,'','blank');
										
										if(count == 8)
											replaceImage('open8',value1+','+value2,'','blank');

										if(countBlankBox == (parseInt(rows)*parseInt(cols))-parseInt(mines))
										{
											for(var k=0;k<len;k++)
											{
												if(document.getElementById(mineX[k]+','+mineY[k]).className=='box2')
													replaceImage('bombflagged',mineX[k]+','+mineY[k],'FLAG','');
											}
											alert("YOU WIN!");
											location.reload();
										}
									}
								}
							}
						}
					}
				}
			}
		</script>
	</div>
</div>
</body>
</html>
