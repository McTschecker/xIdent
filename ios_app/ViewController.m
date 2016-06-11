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
NSTimer *timer;
UILabel *_targetd;
UIButton *update;
UIActivityIndicatorView *indicator;
UILabel *labelcountdown;
int timed;



@implementation ViewController

-(void)viewDidLoad
{
    timed = 20;
    
    [super viewDidLoad];
    NSTimer *counter;
    counter = [NSTimer scheduledTimerWithTimeInterval:1.0 target:self selector:@selector(updatelabelcountdown) userInfo:nil repeats:YES];
    
    UINavigationBar *bar;
    bar = [[UINavigationBar alloc]initWithFrame:CGRectMake(0, 0, self.view.frame.size.width, self.view.frame.size.height/10)];
    [self.view addSubview:bar];
    indicator = [[UIActivityIndicatorView alloc]initWithFrame:CGRectMake(self.view.center.x, self.view.center.y, 10, 10)];
    [self.view addSubview:indicator];
    [indicator startAnimating];
    
    
    int factor;
    factor = 200;
    
    _targetd = [[UILabel alloc]initWithFrame:CGRectMake(self.view.center.x-factor/2,self.view.center.y,factor,factor/3)];
    [self.view addSubview:_targetd];
    _targetd.backgroundColor = [UIColor whiteColor];
    _targetd.textColor = [UIColor blackColor];
    _targetd.layer.borderWidth = 1.0f;
    _targetd.layer.borderColor = [UIColor blackColor].CGColor;
    _targetd.layer.masksToBounds = YES; // Erstellung der Linienränder
    
    
    _targetd.numberOfLines = 5;
    _targetd.textAlignment = NSTextAlignmentCenter;
    
    UIImageView *imageview;
    imageview = [[UIImageView alloc]initWithFrame:CGRectMake((self.view.frame.size.height/2)-((factor/3)*2), self.view.frame.size.height/6, factor/3, factor/3)];
    
    
    int ffactor;
    ffactor = self.view.frame.size.width/5;
    
    update = [[UIButton alloc]initWithFrame:CGRectMake(self.view.center.x-ffactor/2,self.view.frame.size.height/8,ffactor,ffactor)];
    
    [update setTitleColor:[UIColor brownColor] forState:UIControlStateNormal];
    [update setBackgroundImage:[UIImage imageNamed:(@"sync_1.png")] forState:UIControlStateNormal];
    
    _targetd.textColor = [UIColor blackColor];
    
    
    [self.view addSubview:update];
    
    timer = [NSTimer scheduledTimerWithTimeInterval:20 target:self selector:@selector(updatekey) userInfo:nil repeats:YES];
    
    
    [update addTarget:self action:@selector(updatekey) forControlEvents:UIControlEventPrimaryActionTriggered];
    update.imageView.contentMode = UIViewContentModeScaleAspectFit;
    
    update.titleLabel.textAlignment = NSTextAlignmentCenter;
    
    labelcountdown = [[UILabel alloc]initWithFrame:CGRectMake(0, self.view.frame.size.height-200, self.view.frame.size.width, 200)];
    [self.view addSubview:labelcountdown];
    labelcountdown.text = (@"Waiting...");
    labelcountdown.textAlignment = NSTextAlignmentCenter;
    labelcountdown.font = [UIFont boldSystemFontOfSize:self.view.frame.size.width/11];

    
    [self updatekey];
    
    
}
-(BOOL)prefersStatusBarHidden
{
    return YES;
}
-(void)updatekey
{
    timed = 20;
    [timer invalidate];
    timer = nil;
    timer = [NSTimer scheduledTimerWithTimeInterval:20 target:self selector:@selector(updatekey) userInfo:nil repeats:YES];
    printf("\n Updating...");
    [indicator startAnimating];
    
    [self getKEY]; //Key abrufen
    
}
-(void)getKEY
{
    NSString *url_bu;
    
    url_bu = @"";
    NSURLSession *session = [NSURLSession sharedSession];
    NSURLSessionDataTask *dataTask = [session dataTaskWithURL:[NSURL URLWithString:@"https://gf2.noscio.eu"] completionHandler:^(NSData *data, NSURLResponse *response, NSError *error) {
        NSString *stringresult;
        stringresult = [stringresult initWithData:data encoding:NSUTF8StringEncoding];
        [_targetd setText:stringresult];
        
    }];
    [dataTask resume];
    
    
}
-(NSString*)stringgetKeyfromServer
{
    
    NSString *key;
    key = [NSString stringWithContentsOfURL:[NSURL URLWithString:(@"https://gf2.noscio.eu")] encoding:NSUTF8StringEncoding error:nil];
    return key;
    
}
-(void)updatelabelcountdown
{
    timed--;
    if (timed <= 10)
    {
        labelcountdown.textColor = [UIColor orangeColor];
    }
    if (timed <= 5)
    {
        labelcountdown.textColor = [UIColor redColor];
    }
    
    labelcountdown.text = [NSString stringWithFormat:@"%d",timed];
    if (timed == 0)
    {
        timed = 20;
        labelcountdown.textColor = [UIColor blackColor];
    }
}
@end
