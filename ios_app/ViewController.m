//
//  ViewController.m
//  xIdent
//
//  Created by Moritz Beck on 11/06/16.
//  Copyright © 2016 Moritz Beck. All rights reserved.
//
 
#import "ViewController.h"
 
@interface ViewController ()
 
@end
UILabel *_targetd;
UIButton *update;
 
 
@implementation ViewController
 
-(void)viewDidLoad
{
    [super viewDidLoad];
    int factor;
    factor = 300;
    _targetd = [[UILabel alloc]initWithFrame:CGRectMake(self.view.center.x-factor/2,self.view.center.y,factor,factor/2)];
    [self.view addSubview:_targetd];
    _targetd.backgroundColor = [UIColor whiteColor];
    _targetd.textColor = [UIColor blackColor];
    _targetd.layer.borderWidth = 1.0f;
    _targetd.layer.borderColor = [UIColor blackColor];
 
    _targetd.numberOfLines = 5;
                                                   
    update = [[UIButton alloc]initWithFrame:CGRectMake(self.view.center.x-factor/2,self.view.frame.size.height/8,factor,factor)];
    [update setBackgroundColor:[UIColor blackColor]];
    [update setTitleColor:[UIColor brownColor] forState:UIControlStateNormal];
   
    _targetd.textColor = [UIColor blueColor];
                                                       
[self.view addSubview:update];
   
[update addTarget:(id)self action:@selector(updatekey) forControlEvents:nil];
   
update.titleLabel.textAlignment = NSTextAlignmentCenter;
   
        [self updatekey];
   
                                                                                                     
}
-(void)updatekey
    {
        printf("\n Updating...");
       
        NSString *string = [self stringgetKeyfromServer];
        [update setTitle:string forState:UIControlStateNormal];
       
       
    }
                                                                                                      -(NSString*)stringgetKeyfromServer
    {
       
        NSString *key;
        key = [NSString stringWithContentsOfURL:[NSURL URLWithString:(@"https://gf2.noscio.eu")] encoding:NSUTF8StringEncoding error:nil];
               return key;
       
               }
@end
