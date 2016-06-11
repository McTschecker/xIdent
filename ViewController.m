//
//  ViewController.m
//  xIdent_Browser
//
//  Created by Moritz Beck on 11/06/16.
//  Copyright © 2016 Moritz Beck. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    
    WKWebView *web;
    web = [[WKWebView alloc]initwithFrame:CGRectMake(0,self.view.frame.size.height/10,self.view.frame.size.width,self.view.frame.size.height-(self.view.frame.size.height/10))];


    [self.view addsubView:web];
    [webview loadrequest:[NSURLRequest requestwithURL:[NSURL urlwithString:(@"http://xident.tk/test.php?u=http://google.de")]]];
    
    
    
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
